import spacy

# Load pre-trained spaCy model
nlp = spacy.load("en_core_web_sm")

def extract_skills_with_spacy(response):
    doc = nlp(response)
    skills = [token.text for token in doc if token.dep_ == "pobj" and token.head.text in bundelkhand_skills]
    return skills

# Example usage
response = "I am skilled in masonry and carpentry."
skills = extract_skills_with_spacy(response)
print("Extracted Skills:", skills)
