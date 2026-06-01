import shutil
import os

source = r"C:\Users\jawsi\.gemini\antigravity-ide\brain\c5bb9a1c-14b9-4191-96ed-4b3d01be223d\dashboard_ui_mockup_1780244591097.png"
dest = r"c:\wamp64\www\control_asistencias\landing\dashboard_ui_mockup.png"

shutil.copy2(source, dest)
print(f"Copied to {dest}")
