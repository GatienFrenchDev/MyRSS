# Title: Ping Articles Fetcher
# Description: This script pings the server every 2 minutes to fetch all the articles from all the sources.
# Type: Python Script
# Auhtor: Gatien
# Required Libraries: requests, time


import requests
import time

def ping_server():
    url = "http://localhost/scripts/fetch-all-fluxs"
    try:
        response = requests.get(url)
        if response.status_code == 200:
            print("Successfully pinged the server.")
        else:
            print(f"Failed to ping the server. Status code: {response.status_code}")
    except requests.exceptions.RequestException as e:
        print(f"An error occurred: {e}")

if __name__ == "__main__":
    while True:
        ping_server()
        time.sleep(20)  # Sleep for 2 minutes
