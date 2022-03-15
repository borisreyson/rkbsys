#!/usr/bin/env python3
from flask import Flask
app = Flask(__name__)
import face_recognition
from flask import request
from flask import jsonify


@app.route("/", methods=['GET', 'POST'])
def faceid():
    nik = request.args.get("nik")
    newimage = request.args.get("newimage")

    # Load the jpg files into numpy arrays
    wajah_1 = face_recognition.load_image_file(format(nik)+"/wajah_1.jpg")
    wajah_2 = face_recognition.load_image_file(format(nik)+"/wajah_2.jpg")
    wajah_3 = face_recognition.load_image_file(format(nik)+"/wajah_3.jpg")
    unknown_image = face_recognition.load_image_file(format(newimage))

    # Get the face encodings for each face in each image file
    # Since there could be more than one face in each image, it returns a list of encodings.
    # But since I know each image only has one face, I only care about the first encoding in each image, so I grab index 0.
    try:
        wajah_1_face_encoding = face_recognition.face_encodings(wajah_1)[0]
        wajah_2_face_encoding = face_recognition.face_encodings(wajah_2)[0]
        wajah_3_face_encoding = face_recognition.face_encodings(wajah_3)[0]
        unknown_face_encoding = face_recognition.face_encodings(unknown_image)[0]
    except IndexError:
        print("I wasn't able to locate any faces in at least one of the images. Check the image files. Aborting...")
        quit()

    known_faces = [
        wajah_1_face_encoding,
        wajah_2_face_encoding,
        wajah_3_face_encoding
    ]

# results is an array of True/False telling if the unknown face matched anyone in the known_faces array
    results = face_recognition.compare_faces(known_faces, unknown_face_encoding)

    # print("Is the unknown face a picture of Biden? {}".format(results[0]))
    # print("Is the unknown face a picture of Obama? {}".format(results[1]))
    # print("Is the unknown face a new person that we've never seen before? {}".format(not True in results))
   
    if(format(not True in results)==True):
        # return format(not True in results)
        return jsonify({"unknown_face":format(not True in results),"nik":"null" })
    else:
        return jsonify({"unknown_face":format(not True in results),"nik":nik })

    # return nik

if __name__ == "__main__":
    app.run(host='0.0.0.0',port=5000,debug=True)

