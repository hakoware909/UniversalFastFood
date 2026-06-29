<template>
    <div class="settings-page">
      <nav class="profile-nav">
        <router-link to="/order" class="nav-link">
          <i class="fas fa-home"></i> Home
        </router-link>
        <router-link to="/profile" class="nav-link">
          <i class="fas fa-user"></i> My Profile
        </router-link>
        <router-link to="/my-products" class="nav-link">
          <i class="fas fa-box"></i> My Products
        </router-link>
      </nav>
  
      <div class="settings-container">
        <h2>Profile Settings</h2>
        
        <form @submit.prevent="updateProfile" class="settings-form">
          <div class="form-group">
            <label for="name">Name</label>
            <input 
              type="text" 
              id="name"
              v-model="formData.name" 
              required
            />
          </div>
  
          <div class="form-group">
            <label for="email">Email</label>
            <input 
              type="email" 
              id="email"
              v-model="formData.email" 
              required
            />
          </div>
  
          <div class="form-group">
            <label for="number">Contact Number</label>
            <input 
              type="text" 
              id="number"
              v-model="formData.number"
            />
          </div>
  
          <div class="form-group">
            <label for="address">Address</label>
            <textarea 
              id="address"
              v-model="formData.address"
              rows="3"
            ></textarea>
          </div>
  
          <div class="form-group">
            <label for="password">New Password (leave blank to keep current)</label>
            <input 
              type="password" 
              id="password"
              v-model="formData.password"
            />
          </div>
  
          <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input 
              type="password" 
              id="password_confirmation"
              v-model="formData.password_confirmation"
            />
          </div>
  
          <button type="submit" class="save-btn">Save Changes</button>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'ProfileSettings',
    data() {
      return {
        formData: {
          name: '',
          email: '',
          number: '',
          address: '',
          password: '',
          password_confirmation: ''
        }
      }
    },
    methods: {
      async fetchUserData() {
        try {
          const response = await axios.get('/profile');
          const user = response.data.user;
          this.formData.name = user.name;
          this.formData.email = user.email;
          this.formData.number = user.number;
          this.formData.address = user.address;
        } catch (error) {
          console.error('Error fetching user data:', error);
        }
      },
      async updateProfile() {
        try {
          const response = await axios.post('/profile/update', this.formData);
          alert('Profile updated successfully');
        } catch (error) {
          console.error('Error updating profile:', error);
          alert('Error updating profile. Please try again.');
        }
      }
    },
    mounted() {
      this.fetchUserData();
    }
  }
  </script>
  
  <style scoped>
  * {
    margin: 0;
    padding: 0;
  }
  .settings-page {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
  }
  
  .profile-nav {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
  }
  
  .nav-link {
    text-decoration: none;
    color: #333;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s;
  }
  
  .nav-link:hover {
    background-color: #f0f0f0;
  }
  
  .settings-container {
    background: white;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }
  
  .settings-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  .form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  
  label {
    font-weight: 500;
    color: #333;
  }
  
  input, textarea {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
  }
  
  textarea {
    resize: vertical;
  }
  
  .save-btn {
    background-color: #4CAF50;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
  }
  
  .save-btn:hover {
    background-color: #45a049;
  }
  </style> 