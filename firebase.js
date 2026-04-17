// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyADHPuxWWAPKi8nBo3vTzLO4zWIf4e0gjs",
    authDomain: "hafidhportofolio-0.firebaseapp.com",
    projectId: "hafidhportofolio-0",
    storageBucket: "hafidhportofolio-0.firebasestorage.app",
    messagingSenderId: "448225278116",
    appId: "1:448225278116:web:dc57e438ba47712f1a8e31",
    measurementId: "G-PQCDWLYJRV"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);