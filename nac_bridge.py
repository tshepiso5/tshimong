import os
import sys
import json
import network_as_code as nac
from dotenv import load_dotenv

load_dotenv()

def create_test_session(device_id):
    try:
        client = nac.NetworkAsCodeClient(token=os.getenv("NOKIA_API_KEY"))

        device = client.devices.get(device_id)

        location = device.location()

        return{
            "status": "success",
            "device": device_id,
            "location": {
                "latitude": location.latitude,
                "longitude": location.longitude
            }
        }
    except Exception as e:
        return{"status": "error", "message": str(e)}

if __name__ == "__main__":
    target_id = sys.argv[1] if len(sys.argv) > 1 else "tester@testcsp.net"
    print(json.dumps(create_test_session(target_id)))