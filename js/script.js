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

// Gallery expansion fallback for browsers without :has support
(function initGalleryFallback() {
  try {
    if (CSS && CSS.supports && CSS.supports("selector(:has(*))")) {
      return; // native CSS handles expansion
    }
  } catch (e) {
    // If CSS.supports throws, proceed with fallback
  }

  const fieldset = document.querySelector("fieldset");
  if (!fieldset) return;

  const labels = Array.from(fieldset.querySelectorAll(":scope > label"));
  const inputs = labels.map((label) => label.querySelector("input[type=radio]"));

  const applyLayout = (index) => {
    const sizes = ["1fr", "1fr", "1fr", "1fr", "1fr"];
    if (index === 0) {
      sizes[0] = "5fr"; sizes[1] = "3fr";
    } else if (index === 1) {
      sizes[0] = "2fr"; sizes[1] = "5fr"; sizes[2] = "2fr";
    } else if (index === 2) {
      sizes[1] = "2fr"; sizes[2] = "5fr"; sizes[3] = "2fr";
    } else if (index === 3) {
      sizes[2] = "2fr"; sizes[3] = "5fr"; sizes[4] = "2fr";
    } else if (index === 4) {
      sizes[3] = "3fr"; sizes[4] = "5fr";
    }
    // Apply as CSS custom properties expected by CSS
    sizes.forEach((val, i) => {
      fieldset.style.setProperty(`--col-${i + 1}`, val);
    });
  };

  inputs.forEach((input, i) => {
    if (!input) return;
    input.addEventListener("change", () => {
      if (input.checked) applyLayout(i);
    });
  });

  // Initialize based on currently checked input
  const initialIndex = inputs.findIndex((inp) => inp && inp.checked);
  applyLayout(initialIndex >= 0 ? initialIndex : 0);
})();