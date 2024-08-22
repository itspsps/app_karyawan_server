
const video = document.getElementById('video');
//masukan data db ke js dan di parsing 
let dataFaceJson
let dataKaryawanJson
let labelHasil
let nomorTable
function onLoadData(face, karyawan, angka) {
    dataFaceJson = JSON.parse(face);
    dataKaryawanJson = JSON.parse(karyawan);
    nomorTable = angka;
}


navigator.getUserMedia = ( navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);

const startVideo = () => {
    navigator.getUserMedia(
        {video: {}},
        stream => video.srcObject = stream,
        err => console.error(err)
    )
}

Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri('../assets/assets_users/js/face-api.js/models'),
    faceapi.nets.faceLandmark68Net.loadFromUri('../assets/assets_users/js/face-api.js/models'),
    faceapi.nets.faceRecognitionNet.loadFromUri('../assets/assets_users/js/face-api.js/models'),
    faceapi.nets.ssdMobilenetv1.loadFromUri('../assets/assets_users/js/face-api.js/models'),
]).then(startVideo);


video.addEventListener('play', () => {
    let canvas = faceapi.createCanvasFromMedia(video)
    // console.log(canvas);
    // document.getElementById('container').appendChild(canvas)
    const displaySize = {width: video.width, height: video.height}
    faceapi.matchDimensions(canvas, displaySize)

    
    // membuat data sesuai format dari faceapi
    const labeledFaceDescriptors = []
    // console.log(dataFaceJson);
    // console.log(dataFaceJson[0].id);
    // console.log(dataKaryawanJson.find(value => value.id));
    for (let i = 0; i < dataFaceJson.length; i++) {
        const data = dataKaryawanJson.find(value => value.id === dataFaceJson[i].id)
        // console.log(data);  
        
        // rubah dari array biasa menjadi float32Array
        const array1 = JSON.parse(dataFaceJson[i].face_id)
        // console.log(array1);
        const float1 = Float32Array.from(array1)
        // console.log(data.name);
        // console.log(float1);
        
        // memasukan data yang sesuai format ke array labeledFaceDescriptors
        labeledFaceDescriptors.push(new faceapi.LabeledFaceDescriptors(
            data.name,[ float1])
        )

    }

    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.5)
    // me load gambar
    setInterval(async () => {
        const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors()
        const resizedDetections = faceapi.resizeResults(detections, displaySize)
        canvas.willReadFrequently = true;
        canvas.getContext('2d').clearRect(0,0, canvas.width, canvas.height)

        const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor))
        results.forEach((result, i) => {
            const box = resizedDetections[i].detection.box
            const drawBox = new faceapi.draw.DrawBox(box, {label: result.toString()})
            drawBox.draw(canvas)
            labelHasil = drawBox.options.label

        })
    }, 100)

})




