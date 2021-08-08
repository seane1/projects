import socket
import binascii
import requests
import json
import time
import threading
import sys, traceback

UDP_PORT = 10001
message1 = "7E001510010000000000000000FFFE000082"
message2 = "80004C0F94"
URL = "http://192.168.42.129/api/"

def get_general_info():
    print(requests.get(URL + "info.json").content)

def get_program_code(colour):
    switch = {
        "yellow.vsn": "1000",
        "y3.vsn": "2000",
        "new.vsn": "0800",
        "green.vsn": "0100",
        "White.vsn": "0400",
        "green.vsn": "0110",
        "red.vsn": "8080",
        "black.vsn": "0000",
        "Blue.vsn": "0200",
        "yellow.vsn": "1010",
        "y3.vsn": "2020",
        "green.vsn": "0101",
        "Merge Right.vsn": "4040",
        "red1.vsn": "1800",
        "red2.vsn": "2800"
    }
    return str(switch.get(colour))

def get_program():
    try:
        programs = requests.get(URL + "vsns.json", timeout=1).content
        print(programs)
        programs = json.loads(programs)
        print(programs["playing"]["name"])
        return get_program_code(programs["playing"]["name"])
    except Exception:
        print("Can't get program")
        return

def switch_program(code):
    switch = {
        "1000": "yellow",
        "2000": "y3",
        "0800": "new",
        "0100": "green",
        "0400": "White",
        "0110": "green",
        "8080": "red",
        "0000": "black",
        "0200": "Blue",
        "1010": "yellow",
        "2020": "y3",
        "0101": "green",
        "4040": "Merge Right",
        "1800": "red1",
        "2800": "red2"
    }
    try:
        requests.put(URL + "vsns/sources/lan/vsns/" + str(switch.get(code)) + ".vsn/activated", timeout=1)
    except Exception:
        print("can't send now")

def respond(addr):
    response = get_program()
    if response:
        message = message1 + response + message2
        x = (addr[0], UDP_PORT)
        sendsock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        m = message.decode('hex')
        sendsock.connect(x)
        sendsock.sendall(m)
    else:
        return

def wakeup(addr):
    while True:
        respond(addr)
        time.sleep(10)

def main():
    lock = 0
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    s.connect(("8.8.8.8", 80))
    UDP_IP = s.getsockname()[0]
    sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    sock.bind((UDP_IP, UDP_PORT))
    while True:
        data, addr = sock.recvfrom(1024)
        string = binascii.b2a_hex(data)
        switch = string[30]+string[31]
        if switch == "03":
            colour = string[32]+string[33]+string[34]+string[35]
            print(colour)
            switch_program(colour)
            time.sleep(0.05)
        string = ""
        switch = ""
        respond(addr)
        if lock != 1:
            x = threading.Thread(target=wakeup, args=(addr,))
            x.start()
            lock = 1

main()

