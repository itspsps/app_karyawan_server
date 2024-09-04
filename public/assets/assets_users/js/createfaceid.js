const video = document.getElementById('video')
const id_user = document.getElementById('id_user')
const faceid = document.getElementById('faceid')
const select = document.getElementById('name')
const nik = document.getElementById('nik')
const email = document.getElementById('emailFaceId')
const departemen = document.getElementById('departemen')
const form = document.getElementById('form')
const canvas = document.getElementById('canvas')
// memasukan data dari db php ke js
const karyawan = []
function onLoadDataDbKaryawan(value) {
    const dataJson = JSON.parse(value)
    // console.log(dataJson);
    id_user.value = dataJson.id;
    for (let i = 0; i < dataJson.length; i++) {
        karyawan.push(dataJson[i])
    }
}


// navigator usermedia has not supported
navigator.getUserMedia = ( navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);

function startVideo() {
    navigator.getUserMedia(
        {video: {}},
        stream => video.srcObject = stream,
        err => console.error(err)
    )
}
// meload resource face-api.js
Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri('../public/assets/assets_users/js/face-api.js/models'),
    faceapi.nets.faceLandmark68Net.loadFromUri('../public/assets/assets_users/js/face-api.js/models'),
    faceapi.nets.faceRecognitionNet.loadFromUri('../public/assets/assets_users/js/face-api.js/models'),
    faceapi.nets.ssdMobilenetv1.loadFromUri('../public/assets/assets_users/js/face-api.js/models'),
]).then(startVideo);


video.addEventListener('play', () => {
    const displaySize = {width: video.width, height: video.height}
    faceapi.matchDimensions(canvas, displaySize)

    setInterval(async () => {
        const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceDescriptors();
        const resizedDetections = faceapi.resizeResults(detections, displaySize);
        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
    
        //menambkan kan kotak pada muka sebagai tanda pendeteksian wajah berhasil
        faceapi.draw.drawDetections(canvas, resizedDetections);
        // digunakan untuk menampilkan faceLandmark
        faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
        
        const array = resizedDetections[0].descriptor;
        // console.log(array);
        faceid.value = `[${array}]`;
        if (faceid.value != null) {
            // console.log('disable false');
            $('.btn_simpan_face').removeAttr('disabled');
            $('#loading').removeClass();
            $('#loading').addClass('fa fa-save');
            $('.btn_simpan_face').html('Simpan');
        }
    }, 100)
})





