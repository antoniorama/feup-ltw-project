import requests

port = str(input('Port:'))

url = 'http://localhost:{}/database/processes/process_signup.php'.format(port) 

print('Url:' + url + '\n')

# Define users
users = [
    {
        'name': 'Admin Account',
        'username': 'admin',
        'password': 'password123',
        'email': 'admin@example.com',
    },
    {
        'name': 'Jane Smith',
        'username': 'janesmith',
        'password': 'password123',
        'email': 'janesmith@example.com',
    },
    {
        'name': 'Mark Johnson',
        'username': 'markjohnson',
        'password': 'password123',
        'email': 'markjohnson@example.com',
    },
    {
        'name': 'Sarah Wilson',
        'username': 'sarahwilson',
        'password': 'password123',
        'email': 'sarahwilson@example.com',
    }
]

for user in users:
    # Prepare the POST request data
    data = {
        'name': user['name'],
        'username': user['username'],
        'password': user['password'],
        'confirm-password' : user['password'],
        'email': user['email']
    }

    # Send the POST request
    response = requests.post(url, data=data, allow_redirects=True)

    if response.status_code == 200:
        print(f"Account created for {user['username']}")
    else:
        print(f"Failed to create account for {user['username']}")
