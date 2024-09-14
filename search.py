from flask import Flask, request, render_template
from sklearn.feature_extraction.text import TfidfVectorizer
import pandas as pd
import mysql.connector
from mysql.connector import Error

app = Flask(__name__, template_folder='template')

def extract_top_skills(responses, response_idx, top_n=3):
    # Initialize the TF-IDF Vectorizer from Scikit-learn
    vectorizer = TfidfVectorizer(stop_words='english')

    # Fit the vectorizer on the text responses and transform the data
    tfidf_matrix = vectorizer.fit_transform(responses)

    # Convert the TF-IDF matrix into a DataFrame for easier visualization
    df = pd.DataFrame(tfidf_matrix.toarray(), columns=vectorizer.get_feature_names_out())
    
    # Select the specific row corresponding to the response
    row = df.iloc[response_idx]
    
    # Sort the terms by TF-IDF score in descending order and get top terms
    top_terms = row.sort_values(ascending=False).head(top_n)
    
    # Return the top terms (skills)
    return top_terms.index.tolist()

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
                    print(f"Worker ID {id} with Skill '{skill_db}'")
            else:
                print(f"No matches found for skill '{skill}'")
        cursor.close()
        connection.close()
        return redirect_urls
    except Error as e:
        print(f"Error: {e}")
        return []

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/submit', methods=['POST'])
def submit():
    responses = request.form['responses'].strip().split('\n')
    all_redirect_urls = []
    
    if responses:
        for i in range(len(responses)):
            skills = extract_top_skills(responses, i)
            print(f"Top skills in response {i+1}: {skills}")
            redirect_urls = search_skills_in_db(skills)
            all_redirect_urls.extend(redirect_urls)
    else:
        print("No responses to process.")
    
    return f"Redirect URLs: {all_redirect_urls}"

if __name__ == '__main__':
    app.run(debug=True)
