const pianoKeys = document.querySelectorAll(".piano-keys .key"),
  volumeSlider = document.querySelector(".volume-slider input"),
  keysCheckbox = document.querySelector(".keys-checkbox input");

let allKeys = [],
  audio = new Audio(`tunes/a.wav`); // by default, audio src is "a" tune

// Preloader hide on full load
window.addEventListener("load", () => {
  const preloader = document.getElementById("preloader");
  if (preloader) {
    preloader.classList.add("preloader-hide");
    setTimeout(() => preloader.remove(), 400);
  }
});

const playTune = (key) => {
  audio.src = `tunes/${key}.wav`; // passing audio src based on key pressed
  audio.currentTime = 0; // restart audio so repeated presses are responsive
  audio.play(); // playing audio

  const clickedKey = document.querySelector(`[data-key="${key}"]`); // getting clicked key element
  if (clickedKey) {
    clickedKey.classList.add("active"); // adding active class to the clicked key element
    setTimeout(() => {
      // removing active class after 150 ms from the clicked key element
      clickedKey.classList.remove("active");
    }, 150);
  }
};

pianoKeys.forEach((key) => {
  allKeys.push(key.dataset.key); // adding data-key value to the allKeys array
  // calling playTune function with passing data-key value as an argument
  key.addEventListener("click", () => playTune(key.dataset.key));
});

const handleVolume = (e) => {
  audio.volume = e.target.value; // passing the range slider value as an audio volume
};

const showHideKeys = () => {
  // toggling hide class from each key on the checkbox click
  pianoKeys.forEach((key) => key.classList.toggle("hide"));
};

const pressedKey = (e) => {
  // normalize to lowercase so Caps Lock / Shift don't break mapping
  const key = (e.key || "").toLowerCase();
  // if the pressed key is in the allKeys array, only call the playTune function
  if (allKeys.includes(key)) {
    e.preventDefault();
    playTune(key);
  }
};

keysCheckbox.addEventListener("click", showHideKeys);
volumeSlider.addEventListener("input", handleVolume);
document.addEventListener("keydown", pressedKey);
