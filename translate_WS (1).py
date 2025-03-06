from flask import Flask, request, jsonify
from flask_cors import CORS
from deep_translator import GoogleTranslator

app = Flask(__name__)
CORS(app)  

SUPPORTED_LANGUAGES = ['en', 'fr', 'es', 'de', 'ar', 'zh']

@app.route('/translate', methods=['POST'])
def translate():
    data = request.json
    text = data.get('text', '')
    
    if not text:
        return jsonify({'error': 'No text provided'}), 400
    
    try:
        # Traduire en anglais
        english_translation = GoogleTranslator(source='auto', target='en').translate(text)
        
        # Traduire en arabe
        arabic_translation = GoogleTranslator(source='auto', target='ar').translate(text)
        
        return jsonify({
            'original': text,
            'english': english_translation,
            'arabic': arabic_translation
        })
    except Exception as e:
        return jsonify({'error': f'Translation error: {str(e)}'}), 500

if __name__ == '__main__':
    app.run(debug=True)