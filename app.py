@app.route('/article/<int:id>')
def article(id):
    # ... code existant pour récupérer l'article ...
    return render_template('article.html', 
                         titre=article.titre,
                         contenu=article.contenu) 