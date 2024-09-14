# Import necessary libraries from Scikit-learn and MySQL
from sklearn.feature_extraction.text import TfidfVectorizer
import pandas as pd
import mysql.connector

# Define 100 example responses from small gig workers (Bundelkhand specific)
responses = [
    "Skilled in bricklaying, cement mixing, and basic carpentry work.",
    "Experience with plastering, tile fitting, and masonry work.",
    "Proficient in wood carving, stone carving, and traditional craftsmanship.",
    "Worked with iron welding, metal fabrication, and steel cutting.",
    "Knowledge of pottery making, painting murals, and sculpture work.",
    "Specialized in house painting, wall polishing, and plaster finishing.",
    "Expert in bamboo crafts, basket weaving, and rope making.",
    "Worked with thatch roofing, mud plastering, and traditional house construction.",
    "Knowledge of tool repair, metal sharpening, and maintenance work.",
    "Skilled in floor tiling, wall plastering, and structural work.",
    "Proficient in making mud bricks, laying foundations, and building walls.",
    "Worked with glass cutting, window fitting, and door installations.",
    "Experience with welding iron rods, making gates, and sheet cutting.",
    "Proficient in designing wooden furniture, cutting wood, and assembling pieces.",
    "Skilled in decorative plastering, false ceiling work, and interior finishing.",
    "Worked on roadside construction, road leveling, and pothole filling.",
    "Experience in landscaping, garden maintenance, and tree planting.",
    "Knowledge of mixing concrete, pouring foundations, and basic structural work.",
    "Skilled in marble polishing, floor laying, and stonework.",
    "Worked with water pipe installations, fittings, and maintenance.",
    "Proficient in iron bar cutting, binding, and structural assembly.",
    "Knowledge of small engine repair, tool maintenance, and equipment servicing.",
    "Skilled in roof tiling, beam fitting, and load-bearing wall work.",
    "Expert in hand-weaving, fabric dyeing, and textile crafting.",
    "Worked with embroidery, hand-stitching, and threadwork designs.",
    "Experience in stone carving, wall engraving, and monument work.",
    "Knowledge of well digging, water channeling, and irrigation systems.",
    "Skilled in blacksmithing, iron forging, and tool making.",
    "Expert in pottery wheel work, clay shaping, and kiln operation.",
    "Proficient in tile cutting, grouting, and surface leveling.",
    "Worked with scaffolding, beam erection, and concrete reinforcement.",
    "Knowledge of wire fencing, metal fabrication, and farm equipment repair.",
    "Skilled in traditional mud house building, floor leveling, and roof installation.",
    "Proficient in copper work, brass polishing, and metal casting.",
    "Worked with traditional stone flooring, tile cutting, and surface finishing.",
    "Experience in making clay bricks, adobe walls, and cob building.",
    "Skilled in frame construction, pillar casting, and structural support.",
    "Knowledge of road tarring, pothole filling, and path construction.",
    "Proficient in stone block cutting, alignment, and monument building.",
    "Worked on water pipeline installations, connections, and maintenance.",
    "Expert in handmade clay pots, earthenware, and decorative pottery.",
    "Skilled in fabric stitching, pattern making, and dress fitting.",
    "Proficient in ceiling design, POP work, and interior decoration.",
    "Worked with aluminum window fitting, sheet cutting, and door installations.",
    "Knowledge of water channel construction, ditch digging, and farm irrigation.",
    "Skilled in cement block making, wall plastering, and leveling.",
    "Proficient in handmade paper making, dyeing, and drying processes.",
    "Experience with tool sharpening, maintenance, and small equipment repair.",
    "Expert in bamboo stick crafting, hand weaving, and design work.",
    "Worked with traditional wall murals, painting, and decorative art.",
    "Skilled in wiring installation, electrical fitting, and repairs.",
    "Knowledge of mud flooring, clay plastering, and natural material building.",
    "Proficient in making traditional leather footwear, shoe repair, and stitching.",
    "Worked with basic plumbing, pipeline fitting, and maintenance.",
    "Skilled in hand-sewing, fabric dyeing, and textile design.",
    "Expert in thatching roofs, palm leaf weaving, and roof repairs.",
    "Worked with furnace operation, metal smelting, and mold making.",
    "Knowledge of scaffolding, structure assembly, and frame installations.",
    "Proficient in sheet metal work, door fitting, and welding repairs.",
    "Skilled in textile printing, fabric dyeing, and hand weaving.",
    "Experience in plaster carving, wall engraving, and decorative plastering.",
    "Worked with floor polishing, marble cutting, and surface finishing.",
    "Knowledge of rope making, weaving, and knotting.",
    "Proficient in hand painting, decorative murals, and design patterns.",
    "Skilled in load-bearing wall work, cement mixing, and floor finishing.",
    "Worked with traditional floor tile laying, cutting, and leveling.",
    "Proficient in hand-woven baskets, bamboo crafts, and traditional weaving.",
    "Knowledge of tool maintenance, sharpening, and basic repair.",
    "Skilled in ceiling painting, wall decoration, and interior finishing.",
    "Worked with traditional pottery, hand sculpting, and kiln work.",
    "Expert in wood polishing, furniture finishing, and design work.",
    "Skilled in traditional stonework, wall engraving, and monument work.",
    "Proficient in pillar fitting, concrete mixing, and structure assembly.",
    "Worked on metal frame construction, cutting, and welding.",
    "Experience with brick foundation laying, cement mixing, and structure building.",
    "Skilled in hand painting, wall murals, and interior decoration.",
    "Expert in iron gate making, metal sheet cutting, and welding.",
    "Knowledge of fabric stitching, dress making, and pattern work.",
    "Proficient in fence installation, post digging, and wire fitting.",
    "Skilled in irrigation system setup, pipe installation, and maintenance.",
    "Worked with tile setting, surface grouting, and wall polishing.",
    "Knowledge of stone carving, monument design, and engraving.",
    "Proficient in cement block laying, wall construction, and floor leveling.",
    "Skilled in traditional leatherwork, shoe making, and repair.",
    "Worked on metal sheet cutting, welding, and door installation.",
    "Proficient in marble cutting, floor polishing, and stonework.",
    "Skilled in tool sharpening, small repair work, and maintenance.",
    "Worked with hand stitching, fabric design, and pattern creation.",
    "Knowledge of roof installation, tiling, and structural support.",
    "Proficient in load-bearing wall work, bricklaying, and floor laying.",
    "Skilled in water pipe fittings, maintenance, and repair.",
    "Worked with wooden frame cutting, assembly, and furniture making.",
    "Expert in wall plastering, ceiling finishing, and surface design.",
    "Skilled in iron gate making, fence welding, and metal fabrication.",
    "Proficient in wood carving, stone cutting, and sculpture work."
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

# MySQL connection details (update with your own database credentials)
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="1913",
    database="gigconnect"
)

# Create a cursor object to interact with the database
cursor = conn.cursor()

# Create a table for storing skills (if it doesn't already exist)
cursor.execute('''
    CREATE TABLE IF NOT EXISTS worker_skills (
        id INT AUTO_INCREMENT PRIMARY KEY,
        worker_id INT,
        skill VARCHAR(255)
    );
''')

# Insert extracted skills into the MySQL database
def insert_skills_into_db(worker_id, skills):
    for skill in skills:
        cursor.execute("INSERT INTO worker_skills (worker_id, skill) VALUES (%s, %s)", (worker_id, skill))
    conn.commit()  # Commit after inserting all skills for a worker

# Extract skills for each response and insert them into the database
for i in range(len(responses)):
    worker_id = i + 1  # Assuming each response corresponds to a unique worker with a unique ID
    top_skills = extract_top_skills(i)
    insert_skills_into_db(worker_id, top_skills)

# Close the cursor and connection
cursor.close()
conn.close()

print("Skills have been successfully inserted into the MySQL database.")
