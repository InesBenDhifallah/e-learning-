from flask import Flask, request, jsonify
from flask_cors import CORS
from deep_translator import GoogleTranslator

app = Flask(__name__)
CORS(app)

@app.route('/translate', methods=['POST'])
def translate():
    try:
        data = request.get_json()
        text = data.get('text', '')
        
        if not text:
            return jsonify({
                'error': True,
                'message': 'No text provided'
            }), 400

        # Translate to English
        english = GoogleTranslator(source='auto', target='en').translate(text)
        
        # Translate to Arabic
        arabic = GoogleTranslator(source='auto', target='ar').translate(text)

        return jsonify({
            'english': english,
            'arabic': arabic
        })

    except Exception as e:
        return jsonify({
            'error': True,
            'message': str(e)
        }), 500

if __name__ == '__main__':
    app.run(port=5000, debug=True) 