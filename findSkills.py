from transformers import BertTokenizer, BertForTokenClassification
from transformers import pipeline
import torch

# Load pre-trained model and tokenizer
tokenizer = BertTokenizer.from_pretrained('bert-base-uncased')
model = BertForTokenClassification.from_pretrained('bert-base-uncased')

# Define the labels for your specific task
labels = ['O', 'B-Skill', 'I-Skill']  # O: Outside, B-Skill: Beginning of Skill, I-Skill: Inside Skill

# Initialize the pipeline
nlp_pipeline = pipeline('ner', model=model, tokenizer=tokenizer)

def extract_skills(text):
    results = nlp_pipeline(text)
    skills = []
    for entity in results:
        if entity['entity'] == 'B-Skill' or entity['entity'] == 'I-Skill':
            skills.append(entity['word'])
    return ' '.join(skills)

# Example text
text = "I have experience with Python programming, machine learning, and data analysis."

# Extract skills
skills = extract_skills(text)
print("Extracted Skills:", skills)
