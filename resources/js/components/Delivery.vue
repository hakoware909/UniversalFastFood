<template>
    <section>
      <div class="left-container">
        <div class="container">
          <img src="images/drive1.gif" alt="">
          <h1 id="timer">{{ timer }}</h1>
          <p class="message">{{ message }}</p>
        </div>
      </div>
      <div class="right-container">
        <img src="images/drive2.gif" alt="Right Side GIF" style="max-width: 100%;">
      </div>
    </section>
  
    <div class="footerContainer">
      <div class="footerBottom">
        <p>Copyright &copy; 2023; Designed by <span class="designer">Kenny Fazbear</span></p>
      </div>
    </div>
  
    <div class="center">
      <button @click="showPopup">Click this</button>
      <button @click="openReceiptTab">View Receipt</button>
    </div>
  
    <div class="popup" :class="{ active: popupActive }">
      <div class="head">
        <div class="icon">
          <div class="box">
            <i class="fa fa-check"></i>
          </div>
        </div>
      </div>
      <div class="body">
        <h1>Thank You for Buying</h1>
        <p>Just wait for a little while, the delivery rider will call you when he is near your address.</p>
        <button class="close-btn" @click="showPopup">&times; Close</button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        timer: '00:00',
        message: 'Preparing and packing your order...',
        timeSecond: 600, // 10 minutes
        popupActive: false
      };
    },
    created() {
      this.startCountdown();
    },
    methods: {
      startCountdown() {
        const countDown = setInterval(() => {
          this.timeSecond--;
          this.displayTime(this.timeSecond);
  
          if (this.timeSecond === 480) {
            this.message = "The delivery guy got your order";
          } else if (this.timeSecond === 180) {
            this.message = "The delivery guy is around the corner, he will be arriving shortly";
          } else if (this.timeSecond === 0 || this.timeSecond < 1) {
            this.endCount();
            clearInterval(countDown);
          }
        }, 1000);
      },
      displayTime(second) {
        const min = Math.floor(second / 60);
        const sec = second % 60;
        this.timer = `${min < 10 ? '0' + min : min}:${sec < 10 ? '0' + sec : sec}`;
      },
      endCount() {
        this.message = "Thank you for waiting. Your order is on its way!";
        this.timerColor = "green";
        this.showPopup();
      },
      showPopup() {
        this.popupActive = !this.popupActive;
      },
      openReceiptTab() {
        const checkoutData = JSON.parse(sessionStorage.getItem('checkoutData'));
        const cart = JSON.parse(sessionStorage.getItem('cart'));
        const totalQuantity = cart.reduce((total, product) => total + product.quantity, 0);
        const totalPrice = cart.reduce((total, product) => total + (product.price * product.quantity), 0);
  
        const queryParams = `?name=${encodeURIComponent(checkoutData.name)}&phone=${encodeURIComponent(checkoutData.phone)}&address=${encodeURIComponent(checkoutData.address)}&payment=${encodeURIComponent(checkoutData.payment)}&card=${encodeURIComponent(checkoutData.card)}&totalQuantity=${totalQuantity}&totalPrice=${totalPrice}`;
        
        // Open receipt.html in a new tab
        window.open(`receipt.html${queryParams}`, '_blank');
      }
    }
  };
  </script>
  
  <style scoped>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    font-family: "Raleway", sans-serif;
  }
  
  section {
    flex: 1;
    display: flex;
  }
  
  .left-container {
    flex: 1;
    background-color: yellow; /* Yellow background */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    margin-bottom: -20px;
  }
  
  .right-container {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background-size: cover; /* Ensure the GIF covers the container */
  }
  
  .container {
    font-family: 'Courier New', Courier, monospace;
  }
  
  .container img {
    max-width: 80%; /* Adjust the value to resize the image */
    height: auto; /* Maintain the aspect ratio */
    display: block; /* Remove extra space below the image */
    margin: 0 auto; /* Center the image horizontally */
  }
  
  h1 {
    font-family: "Montserrat";
    font-size: 60px; /* Adjust the value to resize the font */
    margin-bottom: 10px; /* Add some space between time and text */
  }
  
  .message {
    font-size: 18px;
    font-weight: bold;
    color: #333; /* Adjust color as needed */
  }
  
  .footerContainer {
    margin-top: 0px;
    flex-shrink: 0;
    color: whitesmoke;
  }
  .left-container, .right-container {
  height: 91vh; /* Full height of the viewport */
}
  
  footer {
    background-color: white;
    margin-top: auto;
  }
  .footerBottom p{
    font-size: 17px;
  }
  
  .footerBottom {
    background-color: rgb(202, 6, 6);
    padding: 30px; /* Adjust the padding as needed */
    text-align: center;
  }
  
  .center {
    position: absolute;
    top: 75%;
    left: 24%;
    transform: translate(-50%, -50%);
  }
  
  .center button {
    padding: 10px 20px;
    background: red;
    color: white;
    border: 1px solid #222;
    outline: none;
    border-radius: 20px;
    font-size: 16px;
    margin-top: 100px;
  }
  
  .popup {
    position: absolute;
    left: 24%; /* Keep this as is for horizontal alignment */
    top: 50%; /* Center vertically; you can adjust this value as needed */
    transform: translate(-50%, -50%) scale(1.2); /* Keep for initial scale effect */
    opacity: 0;
    width: 450px;
    background: #fff;
    border-radius: 5px;
    box-shadow: 2px 2px 5px 2px rgba(0, 0, 0, 0.15);
    transition: opacity 200ms ease-in-out, transform 200ms ease-in-out; /* Optional */
  }
  
  .popup.active {
    top: 63%; /* Adjust this value for the active state */
    opacity: 1;
    transform: translate(-50%, 0) scale(1); /* Remove vertical translation on active */
    transition: top 0ms ease-in-out 0ms, opacity 200ms ease-in-out 0ms, transform 200ms ease-in-out 0ms; /* Keep for smooth transition */
  }       
  
  .popup .head {
    padding: 30px 20px;
    background: #57af57;
    text-align: center;
  }
  
  .popup .head .icon .box {
    display: inline-block;
    width: 80px;
    height: 80px;
    background: #f5f5f5;
    font-size: 40px;
    line-height: 80px;
    color: #57af57;
    border-radius: 50%;
  }
  
  .popup .body {
    padding: 20px;
    text-align: center;
  }
  
  .popup .body h1 {
    font-size: 25px;
    margin-bottom: 10px;
    color: #222;
  }
  
  .popup .body p {
    font-size: 15px;
    color: #555;
    margin-bottom: 20px;
  }
  
  .popup .body .close-btn {
    padding: 10px 20px;
    border: 1px solid #888;
    color: #888;
    background: #fff;
    border-radius: 20px;
    font-size: 16px;
    cursor: pointer;
    outline: none;
  }
  
  .center button:hover {
    background-color: yellow; /* Change to your desired hover color */
    color: #000; /* Change to your desired text color */
    transition: background-color 0.3s ease;
  }
  
  .popup .close-btn:hover {
    background-color: rgb(202, 6, 6); /* Change to your desired hover color */
    color: #000; /* Change to your desired text color */
    transition: background-color 0.3s ease;
  }
  </style>
  