
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
//masukan data db ke js dan di parsing 
let dataFaceJson
let dataKaryawanJson
let labelHasil
let nomorTable
function onLoadData(face, karyawan, angka) {
    // console.log(face);
    dataFaceJson = JSON.parse(face);
    // console.log(dataFaceJson);
    dataKaryawanJson = JSON.parse(karyawan);
    nomorTable = angka;
}


navigator.getUserMedia = ( navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);

const startVideo = () => {
    navigator.getUserMedia(
        {video: {}},
        stream => video.srcObject = stream,
        err => Swal.fire({
           title: 'Error',
            icon: 'error',
            text: err,
        })
    )
}

// console.log('as');
Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri('../assets/assets_users/js/face-api.js/models'),
    faceapi.nets.faceLandmark68Net.loadFromUri('../assets/assets_users/js/face-api.js/models'),
    faceapi.nets.faceRecognitionNet.loadFromUri('../assets/assets_users/js/face-api.js/models'),
    faceapi.nets.ssdMobilenetv1.loadFromUri('../assets/assets_users/js/face-api.js/models'),
    // faceapi.nets.faceExpressionNet.loadFromUri('../assets/assets_users/js/face-api.js/models'),
]).then(startVideo);

Swal.fire({
    allowOutsideClick: false,
    // background: 'transparent',
    position: 'bottom',
    html: '<div class="me-2 mb-2 d-flex align-items-center text-center"><span class="spinner-border me-3 spinner-border-sm text-primary" role="status" aria-hidden="true"></span>Camera&nbsp;Loading...</div>',
    showCancelButton: false,
    showConfirmButton: false,
  
});
video.addEventListener('play', () => {
    Swal.close()
    Swal.fire({
        allowOutsideClick: false,
        // background: 'transparent',
        position: 'bottom',
        html: '<div style="width:100%; font-size:10pt;" class="me-2 mb-2 d-flex align-items-center text-center"><span class="spinner-border me-3 spinner-border-sm text-primary" role="status" aria-hidden="true"></span>Letakkan&nbsp;Wajah&nbsp;Anda&nbsp;Didepan&nbsp;Kamera..</div>',
        showCancelButton: false,
        showConfirmButton: false,
      
    });
    // console.log(canvas);
    // document.getElementById('container').appendChild(canvas)
    const displaySize = {width: video.width, height: video.height}
    faceapi.matchDimensions(canvas, displaySize)

    
    // membuat data sesuai format dari faceapi
    const labeledFaceDescriptors = []
    for (let i = 0; i < dataFaceJson.length; i++) {
        const data = dataKaryawanJson.find(value => value.id === dataFaceJson[i].id)
        
        const array1 = JSON.parse(dataFaceJson[i].face_id)
        
        const float1 = Float32Array.from(array1)
    
        
        // memasukan data yang sesuai format ke array labeledFaceDescriptors
        labeledFaceDescriptors.push(new faceapi.LabeledFaceDescriptors(
            data.name,[ float1])
        )

    }

    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.5)
    // me load gambar
    setInterval(async () => {
        const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks()
            .withFaceDescriptors()
        const resizedDetections = faceapi.resizeResults(detections, displaySize)
        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height)
    
        //menambkan kan kotak pada muka sebagai tanda pendeteksian wajah berhasil
        faceapi.draw.drawDetections(canvas, resizedDetections)
        faceapi.draw.drawFaceLandmarks(canvas, resizedDetections)

        const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor))
        results.forEach((result, i) => {
            const box = resizedDetections[i].detection.box
            const drawBox = new faceapi.draw.DrawBox(box, {label: result.toString()})
            drawBox.draw(canvas)
            // console.log(drawBox);
            labelHasil = drawBox.options.label

        })
    }, 3000)

})





