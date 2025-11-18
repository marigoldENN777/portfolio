import tkinter as tk
import sounddevice as sd
from scipy.io.wavfile import write
import numpy as np

sample_rate = 44100
recording = []
stream = None
DEVICE_INDEX = 4

sd.default.device = (DEVICE_INDEX, None)
def start_recording():
    global stream, recording
    recording = []

    stream = sd.InputStream(
        samplerate=sample_rate,
        channels=1,
        callback=callback
    )
    stream.start()
    status_label.config(text="Recording...")

def callback(indata, frames, time, status):
    global recording
    recording.append(indata.copy())

def stop_recording():
    global stream, recording
    if stream:
        stream.stop()
        stream.close()
        stream = None

    audio = np.concatenate(recording)
    write("output.wav", sample_rate, audio)
    status_label.config(text="Saved as output.wav")

root = tk.Tk()
root.title("Audio Recorder")

start_btn = tk.Button(root, text="Start Recording", command=start_recording)
start_btn.pack(pady=10)

stop_btn = tk.Button(root, text="Stop Recording", command=stop_recording)
stop_btn.pack(pady=10)

status_label = tk.Label(root, text="")
status_label.pack()

root.mainloop()
