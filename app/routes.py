from flask import render_template, request, redirect, url_for, flash, session
from flask_login import login_user, logout_user, login_required, current_user
from app import app, mysql, bcrypt
from app.models import User

@app.route('/')
def index():
    cur = mysql.connection.cursor()
    cur.execute("SELECT * FROM products WHERE is_active = TRUE")
    products = cur.fetchall()
    return render_template('index.html', products=products)

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        cur = mysql.connection.cursor()
        cur.execute("SELECT id, username, email, password_hash, role FROM users WHERE username = %s", (username,))
        result = cur.fetchone()
        if result and bcrypt.check_password_hash(result[3], password):
            user = User(result[0], result[1], result[2], result[4])
            login_user(user)
            return redirect(url_for('index'))
        flash('Invalid username or password')
    return render_template('login.html')

@app.route('/logout')
@login_required
def logout():
    logout_user()
    return redirect(url_for('index'))

@app.route('/product/<int:product_id>')
def view_product(product_id):
    cur = mysql.connection.cursor()
    cur.execute("SELECT * FROM products WHERE id = %s", (product_id,))
    product = cur.fetchone()
    return render_template('product.html', product=product)