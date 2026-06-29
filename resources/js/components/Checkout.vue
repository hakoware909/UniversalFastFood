<template>
    <div class="background-image"></div>
    <div class="container">
      <div class="checkoutLayout">
        <div class="returnCart">
          <router-link to="/order">Keep Shopping</router-link>
          <h1>List Product in Cart</h1>
          <div class="list">
            <div v-for="(product, index) in cartItems" :key="index" class="item">
              <img :src="product.image" alt="Product" />
              <div class="name">{{ product.name }}</div>
              <div class="price-quantity">
            <span class="returnPrice">₱{{ product.price.toFixed(2) }}</span> 
            <span class="quantity"> x {{ product.quantity }}</span>
          </div>
            </div>
          </div>
        </div>
  
        <div class="right">
          <h1>Checkout</h1>
          <form @submit.prevent="handleCheckout">
            <div class="form">
              <div class="group">
                <label for="name">Full Name</label>
                <input v-model="checkoutData.name" type="text" id="name" required />
              </div>
              <div class="group">
                <label for="phone">Phone Number</label>
                <input v-model="checkoutData.phone" type="number" id="phone" required />
              </div>
              <div class="group">
                <label for="address">Address</label>
                <input v-model="checkoutData.address" type="text" id="address" required />
              </div>
              <div class="group">
                <label for="payment">Payment</label>
                <select v-model="checkoutData.payment" id="payment" required>
                  <option value="">Choose..</option>
                  <option value="COD">Cash on Delivery</option>
                  <option value="COPU">Cash on Pick Up</option>
                  <option value="GCASH">GCASH</option>
                </select>
              </div>
              <div class="group">
                <label for="card">If Card for Payment</label>
                <select v-model="checkoutData.card" id="card">
                  <option value="">Choose..</option>
                  <option value="Metrobank">Metrobank</option>
                  <option value="ChinaBank">ChinaBank</option>
                </select>
              </div>
            </div>
            <div class="return">
              <div class="row">
                <div>Total Quantity</div>
                <div class="totalQuantity">{{ totalQuantity }}</div>
              </div>
              <div class="row">
                <div>Total Price</div>
                <div class="totalPrice">₱ {{ totalPrice.toFixed(2) }}</div>
              </div>
            </div>
            <button type="submit" class="buttonCheckout">CHECKOUT</button>
          </form>
          <div class="popup" :class="{ 'open-popup': popupOpen }">
            <img src="images/tick.png" />
            <h2>Please Wait</h2>
            <h1>Your order is being processed, please wait till the delivery rider calls your number. Thank you!</h1>
            <button @click="closePopup">OK</button>
          </div>
        </div>
      </div>
      <div class="footer">
      <p>Copyright &copy;2023; Designed by <span class="designer">Kenny Fazbear</span></p>
    </div>
</div>
    <!--- <footer class="footer">
    <p>Copyright &copy; 2023; Designed by <span class="designer">Kenny Fazbear</span></p>
</footer> -->

  </template>
  
  <script>
  export default {
    data() {
      return {
        checkoutData: {
          name: '',
          phone: '',
          address: '',
          payment: '',
          card: ''
        },
        cartItems: [], // This will hold the cart data
        popupOpen: false,
      };
    },
    computed: {
      totalQuantity() {
        return this.cartItems.length;
      },
      totalPrice() {
        return this.cartItems.reduce((total, item) => total + parseFloat(item.price), 0);
      }
    },
    methods: {
      // Handle form validation and submission
      handleCheckout() {
        if (this.validateForm()) {
          this.openPopup();
          this.saveCheckoutData();
          setTimeout(() => {
            this.$router.push('/delivery'); // Navigate to the delivery page after a delay
          }, 3000);
        }
      },
      // Validate form fields
      validateForm() {
        if (!this.checkoutData.name || !this.checkoutData.phone || !this.checkoutData.address || !this.checkoutData.payment) {
          alert("Please fill out all required fields before checking out.");
          return false;
        }
        return true;
      },
      // Open the popup
      openPopup() {
        this.popupOpen = true;
      },
      // Close the popup
      closePopup() {
        this.popupOpen = false;
      },
      // Save the checkout data and cart items to sessionStorage
      saveCheckoutData() {
        const dataToSave = {
          checkoutData: this.checkoutData,
          cartItems: this.cartItems
        };
        sessionStorage.setItem('checkoutData', JSON.stringify(dataToSave));
      }
    },
    mounted() {
      // Load cart items from sessionStorage or localStorage
      const cart = JSON.parse(localStorage.getItem('cart')) || [];  // Use localStorage instead of sessionStorage
      this.cartItems = cart;
      console.log(this.cartItems);
    }
    
  };
  </script>
  
<style scoped>
        body {      
            margin: 0;
            padding: 0;
            font-family: "Raleway", sans-serif;
            overflow-x: hidden;
            font-synthesis: 15px;
            position: relative;
            min-height: 100vh; /* Ensure the body takes up at least the full height of the viewport */
            display: flex;
            flex-direction: column;
        }
        body::before {
            content: "";
            position: fixed; /* Use fixed to cover the entire viewport */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url(@/assets/images/gallery/2jollibee.jpg);
            background-size: cover;
            background-position: center;
            opacity: 0.35; /* Set the desired opacity for the background image */
            z-index: -1; /* Keeps it behind the content */
        }
        
        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url(images/gallery/2jollibee.jpg);
            background-size: cover;
            background-position: center;
            opacity: 0.5;
            z-index: -1;
        }
        .container {
            display: flex; /* Enables flexbox */
            justify-content: center; /* Centers content horizontally */
            align-items: center; /* Centers content vertically */
            flex-direction: column; /* Stacks content vertically */
            flex-grow: 1; /* This makes the container grow to take up remaining space */
            height: 80vh; /* Full height of the viewport */
            padding-top: 200px; /* Moves the content slightly down */
            box-sizing: border-box; /* Includes padding in the total height/width */
        }
        a { 
            text-decoration: none;
            margin-top: 30%;
            padding: 10px 20px; /* Add padding for some spacing inside the button */
            font-weight: bold;
            color: #fff; /* Text color */
            background-color: #5358B3; /* Button background color */
            border: 2px solid #5358B3; /* Border with same color as the background */
            border-radius: 20px; /* Rounded corners */
            transition: background-color 0.3s, color 0.3s; /* Smooth hover effect */
        }

        a:hover {
            background-color: #49D8B9; /* Change background color on hover */
            color: white; /* Change text color on hover */
            border-color: #49D8B9; /* Maintain border color */
        }
        .checkoutLayout {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 80px;
            padding: 20px;
            
        }
        .checkoutLayout .right {
            background-color: #5358B3;
            border-radius: 20px;
            padding: 40px; /* Reduced padding */
            color: #fff;
            /* Optional: Set a max-height if needed */
            /*max-height: calc(80vh - 70px); /* Adjust based on your layout */
            overflow: auto; /* Add scroll if content exceeds the height */
            margin-top: 40px; /* Adjust to move down */
            margin-bottom: 190px; /* Adjust to move up */
        }

        .checkoutLayout .right .form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            border-bottom: 1px solid #7a7fe2;
            padding-bottom: 20px;
        }
        .checkoutLayout .right h1 {
            margin-bottom: 40px; /* Adjust as needed */
        }       

        .checkoutLayout .form h1,
        .checkoutLayout .form .group:nth-child(-n+3) {
            grid-column-start: 1;
            grid-column-end: 3;
        }
        .checkoutLayout .form input, 
        .checkoutLayout .form select {
            width: 100%;
            padding: 10px 20px;
            box-sizing: border-box;
            border-radius: 20px;
            margin-top: 10px;
            border: none;
            background-color: #6a6fc9;
            color: #fff;
        }
        .checkoutLayout .right .return .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .checkoutLayout .right .return .row div {
            font-weight: bold;
            font-size: 20px; /* Adjust this value to change font size */
        }
        .checkoutLayout .right .return .row div:nth-child(2) {
            font-weight: bold;
            font-size: x-large;
            font-size: 20px; /* Adjust this value to change font size */
        }
        .buttonCheckout {
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 20px;
            background-color: #49D8B9;
            margin-top: 35px;
            font-weight: bold;
            color: #fff;
            cursor: pointer;
        }
        .buttonCheckout:hover {
            opacity: 0.8;
        }
        .returnCart h1 {
            border-top: 5px solid #eee;  
            padding: 20px 0;
            margin-top: 30px;
        }
        .returnCart .list .item img {
            height: 80px;
        }
        .returnCart .list .item {
            display: grid;
            grid-template-columns: 125px 1fr 50px 80px;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            background-color: rgba(255, 255, 255, 0.9); /* White background with slight opacity */
            padding: 20px; /* Add padding to create space inside the box */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); /* Subtle shadow for elevation effect */
            border-radius: 10px; /* Rounded corners for a smoother look */
            transition: transform 0.3s; /* Adds smooth hover effect */
        }

        .returnCart .list .item:hover {
            transform: scale(1.02); /* Slight zoom effect on hover */
        }
        .returnCart .list .item .name,
        .returnCart .list .item .returnPrice {
            font-size: large;
            font-weight: bold;
        }
        .popup{
            width: 400px;
            background: #fff;
            border-radius: 6px;
            position: absolute;
            top: 0%;
            left: 50%;
            transform: translate(-50%, -50%)scale(0.1);
            text-align: center;
            padding: 0 30px 30px;
            color: #333;
            visibility: hidden          ;
            transition: transform 0.4s, top 0.4s;
        }
        .open-popup{
            visibility: visible;
            top: 50%;
            transform: translate(-50%, -50%)scale(1);
            }

        .popup img{
            width: 100px;
            margin-top: -50px;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }
        .popup h2{
            font-size: 38px;
            font-weight: 500;
            margin: 30px 0 10px;
            }
        .pop p{
            font-size: 10px;
            }
        .popup button{
            width: 100%;
            margin-top: 50px;
            padding: 10px 0;
            background: #6fd649;
            color: #fff;
            border: 0;
            outline: none;
            font-size: 18px;
            border-radius: 4px;
            cursor: pointer;
            box-shadow: 0 5px 5px rgba(0, 0, 0, 0.2);
            }
        /*footer {
            background-color: red;
            color: white;
            text-align: center;
            padding: 20px 0;
            position: relative;
            width: 100%;
            margin-top: auto;  Pushes the footer to the bottom 
        } */
        .footer {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background-color: rgb(202, 6, 6);
  padding: 35px;
  text-align: center;
}

.footer p {
  color: white;
  margin: 0;
  font-size: 15px;
}

.quantity {
    font-size: 1.5em;  /* Increase font size */
    font-weight: bold;  /* Make the quantity text bold */
    margin-left: 10px;  /* Optional: Add some spacing between price and quantity */
}

</style>
  