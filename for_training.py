import spacy
from spacy.training import Example

# Load or create a blank model
nlp = spacy.blank("en")

# Add NER to the pipeline
ner = nlp.add_pipe("ner")

# Add the SKILL label to the NER
ner.add_label("SKILL")

# Define your training data
TRAIN_DATA = [
    ("I am a skilled mason with 5 years of experience building houses.", {"entities": [(10, 15, "SKILL")]}),
    ("I specialize in woodworking and furniture making.", {"entities": [(19, 30, "SKILL"), (35, 51, "SKILL")]}),
    # Add more annotated examples
]

# Initialize the model
nlp.begin_training()

# Training loop
for epoch in range(10):  # Adjust the number of epochs as needed
    for text, annotations in TRAIN_DATA:
        doc = nlp.make_doc(text)
        example = Example.from_dict(doc, annotations)
        nlp.update([example], drop=0.5, losses={})

# Save the trained model
nlp.to_disk("trained_ner_model")
