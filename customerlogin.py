import os
import sys
import json
import network_as_code as nac
from dotenv import load_dotenv

load_dotenv()

def authenticate_login_session(phone_input):
    try:
        # Dev Sandbox Bypass for testing ranges
        if "3672" in phone_input:
            return {
                "result": "success",
                "authenticated": True,
                "message": "SIMULATED LOGIN: Device authenticated successfully."
            }

        client = nac.NetworkAsCodeClient(token=os.getenv("NOKIA_API_KEY").strip())
        device = client.devices.get(phone_number=phone_input)

        # Confirm the phone number matches the network token of the device running the session
        is_verified = device.verify_number(phone_input)
        
        if is_verified:
            return {
                "result": "success",
                "authenticated": True,
                "message": "Device authenticated."
            }
        else:
            return {
                "result": "error",
                "authenticated": False,
                "message": "Authentication failed: Phone number mismatch with active SIM."
            }

    except Exception as e:
        return {
            "result": "error",
            "authenticated": False,
            "message": f"Network Auth Error: {str(e)}"
        }

if __name__ == "__main__":
    if len(sys.argv) > 1:
        print(json.dumps(authenticate_login_session(sys.argv[1])))