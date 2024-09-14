# Import necessary libraries
from sklearn.feature_extraction.text import TfidfVectorizer
import pandas as pd
import mysql.connector
from mysql.connector import Error

# Bundelkhand-specific responses representing demands for gig workers
responses = [
    "Looking for skilled workers in bricklaying and cement plastering for rural construction.",
    # "Need artisans experienced in bamboo crafting and traditional weaving for local handicrafts.",
    # "Require workers proficient in mud plastering and thatch roofing for village homes.",
    # "Seeking carpenters for making wooden furniture and traditional wooden structures.",
    # "In need of masons skilled in stone carving and wall construction for heritage sites.",
    # "Experienced in tile fitting and plaster work needed for building renovation projects."
]

# Initialize the TF-IDF Vectorizer from Scikit-learn
vectorizer = TfidfVectorizer(stop_words='english')

# Fit the vectorizer on the text responses and transform the data
tfidf_matrix = vectorizer.fit_transform(responses)

# Convert the TF-IDF matrix into a DataFrame for easier visualization
df = pd.DataFrame(tfidf_matrix.toarray(), columns=vectorizer.get_feature_names_out())

# Function to extract the top N terms (skills) from each response based on their TF-IDF scores
def extract_top_skills(response_idx, top_n=3):
    # Select the specific row corresponding to the response
    row = df.iloc[response_idx]
    
    # Sort the terms by TF-IDF score in descending order and get top terms
    top_terms = row.sort_values(ascending=False).head(top_n)
    
    # Return the top terms (skills)
    return top_terms.index.tolist()

# Connect to MySQL Database and generate redirect URLs
def search_skills_in_db(skills):
    try:
        connection = mysql.connector.connect(
            host='localhost',
            database='gigconnect',
            user='root',
            password='1913'
        )
        cursor = connection.cursor()
        redirect_urls = []
        for skill in skills:
            # Make skill matching case-insensitive
            query = """
                SELECT id, skills 
                FROM gig_workers 
                WHERE LOWER(skills) LIKE LOWER(%s)
            """
            cursor.execute(query, ('%' + skill + '%',))
            results = cursor.fetchall()
            if results:
                for row in results:
                    id, skill_db = row
                    # Generate URL for redirection
                    url = f"{id}"
                    redirect_urls.append(url)
                    print(f" Worker ID {id} with Skill '{skill_db}")
            else:
                print(f"No matches found for skill '{skill}'")
        cursor.close()
        connection.close()
        return redirect_urls
    except Error as e:
        print(f"Error: {e}")
        return []

# Iterate over each response to extract top skills and generate redirection URLs
all_redirect_urls = []
if responses:
    for i in range(len(responses)):
        skills = extract_top_skills(i)
        print(f"Top skills in response {i+1}: {skills}")
        redirect_urls = search_skills_in_db(skills)
        all_redirect_urls.extend(redirect_urls)
else:
    print("No responses to process.")

# Optionally, you could now process `all_redirect_urls` as needed
