from flask_login import UserMixin
from app import mysql, login_manager

class User(UserMixin):
    def __init__(self, id, username, email, role):
        self.id = id
        self.username = username
        self.email = email
        self.role = role

@login_manager.user_loader
def load_user(user_id):
    cur = mysql.connection.cursor()
    cur.execute("SELECT id, username, email, role FROM users WHERE id = %s", (user_id,))
    result = cur.fetchone()
    if result:
        return User(*result)
    return None