// Register Service worker to control making site work offline
if ('serviceWorker' in navigator) {
	navigator.serviceWorker
	.register('/assets/assets_users/app.js')
	.then(() => { console.log('Service Worker Registered'); });
}

// Code to handle install prompt on desktop
let deferredPrompt;
const pwaBtn = document.querySelector('.pwa-btn');
const installText = document.querySelector('.pwa-text');
//pwaBtn.style.display = 'none';

/* for ios start */
function isThisDeviceRunningiOS(){
  if (['iPad Simulator', 'iPhone Simulator','iPod Simulator', 'iPad','iPhone','iPod','ios'].includes(navigator.platform) || navigator.userAgent.indexOf('Mac OS X') != -1){
	installText.innerHTML = document.querySelector('.pwa-text');
  //pwaBtn.style.display = 'none';
	pwaBtn.remove();
	return true;
  }
}
isThisDeviceRunningiOS();
/* for ios start */

window.addEventListener('beforeinstallprompt', (e) => {
  // Prevent Chrome 67 and earlier from automatically showing the prompt
  e.preventDefault();
  // Stash the event so it can be triggered later.
  deferredPrompt = e;
  // Update UI to notify the user they can add to home screen
  //pwaBtn.style.display = 'block';

  pwaBtn.addEventListener('click', () => {
    // hide our user interface that shows our A2HS button
    //pwaBtn.style.display = 'none';
    // Show the prompt
    deferredPrompt.prompt();
    // Wait for the user to respond to the prompt
    deferredPrompt.userChoice.then((choiceResult) => {
      if (choiceResult.outcome === 'accepted') {
        console.log('User accepted the A2HS prompt');
      } else {
        console.log('User dismissed the A2HS prompt');
      }
      deferredPrompt = null;
    });
  });
});
