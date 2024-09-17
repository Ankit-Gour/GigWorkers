import nltk
from nltk.corpus import wordnet
from nltk.stem import WordNetLemmatizer
from flashtext import KeywordProcessor

# Download necessary NLTK data (run this once)
nltk.download('punkt')
nltk.download('averaged_perceptron_tagger')
nltk.download('wordnet')

# Initialize lemmatizer
lemmatizer = WordNetLemmatizer()

# Function to get wordnet POS tag
def get_wordnet_pos(word):
    """Map POS tag to first character lemmatize() accepts"""
    tag = nltk.pos_tag([word])[0][1][0].upper()
    tag_dict = {"J": wordnet.ADJ, "N": wordnet.NOUN, "V": wordnet.VERB, "R": wordnet.ADV}
    return tag_dict.get(tag, wordnet.NOUN)

# Define a function to preprocess text (lemmatization)
def preprocess_text(text):
    tokens = nltk.word_tokenize(text)
    lemmatized_tokens = [lemmatizer.lemmatize(token, get_wordnet_pos(token)) for token in tokens]
    return ' '.join(lemmatized_tokens)

# Define synonym mapping for skills
skill_synonyms = {
    'drive': 'driving',
    'tractor': 'tractor driving',
    'car': 'car driving'
}

# Function to replace synonyms in text
def replace_synonyms(text, synonym_dict):
    words = text.split()
    for i, word in enumerate(words):
        if word in synonym_dict:
            words[i] = synonym_dict[word]
    return ' '.join(words)

# Predefined list of skills
skills_list = ["tractor driving", "driving", "car driving"]

# Create a keyword processor
keyword_processor = KeywordProcessor()
for skill in skills_list:
    keyword_processor.add_keyword(skill)

# Input text
text = "I can drive tractor"

# Preprocess the input text (lemmatization and synonym replacement)
preprocessed_text = preprocess_text(text)
text_with_synonyms = replace_synonyms(preprocessed_text, skill_synonyms)

# Extract skills from the processed text
extracted_skills = keyword_processor.extract_keywords(text_with_synonyms)
print(extracted_skills)  # Output: ['tractor driving', 'driving']
