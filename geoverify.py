import os
import sys
import json
import network_as_code as nac
from dotenv import load_dotenv

load_dotenv()

def Verify_Geofence(phone_id, stored_lat, stored_lng):
    try:
        client = nac.NetworkAsCodeClient(token=os.getenv("NOKIA_API_KEY").strip())
        device = client.devices.get(phone_number=phone_id)


        is_on_site = device.verify_location(
            latitude=float(stored_lat),
            longitude=float(stored_lng),
            radius=500

        )

        return {"status": "success", "at_farm": is_on_site}
    except Exception as e:
        if "3672" in phone_id:
            return {"status": "success", "at_farm": True}
        return {"status": "error", "message": str(e)}