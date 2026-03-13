/**
 * Video Block JavaScript
 * Handles video player initialization and interactions
 */

(function () {
  "use strict";

  class VideoPlayer {
    constructor(element) {
      this.element = element;
      this.videoType = element.dataset.videoType || "file";
      this.init();
    }

    init() {
      if (this.videoType === "file") {
        this.initFileVideo();
      } else if (this.videoType === "youtube") {
        this.initYouTubeVideo();
      }
    }

    initFileVideo() {
      const videoElement = this.element.querySelector(".video__element");

      if (!videoElement) return;

      // Ensure video has proper controls
      videoElement.controls = true;

      // Add custom event listeners for enhanced functionality
      videoElement.addEventListener("play", () => {
        this.onPlay();
      });

      videoElement.addEventListener("pause", () => {
        this.onPause();
      });

      videoElement.addEventListener("ended", () => {
        this.onEnded();
      });

      // Pause other videos when this one plays
      videoElement.addEventListener("play", () => {
        this.pauseOtherVideos();
      });

      // Keyboard shortcuts
      videoElement.addEventListener("keydown", (e) => {
        this.handleKeyboardShortcuts(e, videoElement);
      });
    }

    initYouTubeVideo() {
      const player = this.element.querySelector(".video__player--youtube");
      const iframe = this.element.querySelector(".video__iframe");

      if (!player || !iframe) return;

      // Add a loading indicator
      player.classList.add("video__player--loading");

      // Check if iframe is loaded
      iframe.addEventListener("load", () => {
        player.classList.remove("video__player--loading");
      });

      // Fallback: remove loading indicator after a timeout
      setTimeout(() => {
        player.classList.remove("video__player--loading");
      }, 3000);
    }

    pauseOtherVideos() {
      // Pause all other video elements on the page
      const allVideos = document.querySelectorAll(".video__element");
      allVideos.forEach((video) => {
        if (video !== this.element.querySelector(".video__element")) {
          video.pause();
        }
      });
    }

    handleKeyboardShortcuts(event, videoElement) {
      switch (event.key) {
        case " ":
          // Spacebar to play/pause
          event.preventDefault();
          if (videoElement.paused) {
            videoElement.play();
          } else {
            videoElement.pause();
          }
          break;
        case "f":
          // F for fullscreen
          if (videoElement.requestFullscreen) {
            videoElement.requestFullscreen();
          }
          break;
        case "m":
          // M to toggle mute
          event.preventDefault();
          videoElement.muted = !videoElement.muted;
          break;
        case "ArrowRight":
          // Arrow right to skip 5 seconds
          event.preventDefault();
          videoElement.currentTime = Math.min(
            videoElement.currentTime + 5,
            videoElement.duration,
          );
          break;
        case "ArrowLeft":
          // Arrow left to rewind 5 seconds
          event.preventDefault();
          videoElement.currentTime = Math.max(videoElement.currentTime - 5, 0);
          break;
        case "0":
        case "1":
        case "2":
        case "3":
        case "4":
        case "5":
        case "6":
        case "7":
        case "8":
        case "9":
          // Number keys to jump to percentage
          event.preventDefault();
          const percentage = parseInt(event.key) * 10;
          videoElement.currentTime = (percentage / 100) * videoElement.duration;
          break;
      }
    }

    onPlay() {
      // Track video play event
      if (window.gtag) {
        gtag("event", "video_play", {
          video_title:
            this.element.querySelector(".video__title")?.textContent ||
            "Unknown",
        });
      }
    }

    onPause() {
      // Track video pause event
    }

    onEnded() {
      // Track when video finishes
      if (window.gtag) {
        gtag("event", "video_complete", {
          video_title:
            this.element.querySelector(".video__title")?.textContent ||
            "Unknown",
        });
      }
    }
  }

  // Initialize all video players on the page
  function initVideos() {
    const videoContainers = document.querySelectorAll(".video__container");
    videoContainers.forEach((container) => {
      new VideoPlayer(container);
    });
  }

  // Initialize when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initVideos);
  } else {
    initVideos();
  }
})();
