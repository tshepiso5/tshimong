import os
import sys
import json
import network_as_code as nac
from dotenv import load_dotenv

load_dotenv()

def get_current_coordinates(phone_input):
    try:
        client = nac.NetworkAsCodeClient(token=os.getenv("NOKIA_API_KEY").strip())
        device = client.devices.get(phone_number=phone_input)

        # Retrieve the current network-based location
        location = device.location()

        return {
            "status": "success",
            "latitude": location.latitude,
            "longitude": location.longitude,
            "message": "Coordinates retrieved from network tower"
        }
    except Exception as e:
        # Mock for Dev/Simulator testing in South Africa
        # Returns a coordinate in a typical agricultural area (e.g., near Soweto/West Rand)
        if "3672" in phone_input:
            return {
                "status": "success",
                "latitude": -26.2485, 
                "longitude": 27.8540,
                "message": "SIMULATED COORDINATES (Dev Mode)"
            }
        return {"status": "error", "message": str(e)}

if __name__ == "__main__":
    print(json.dumps(get_current_coordinates(sys.argv[1])))