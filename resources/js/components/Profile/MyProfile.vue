<template>
  <div class="profile-page">
    <!-- Navigation -->
    <nav class="profile-nav">
      <router-link to="/order" class="nav-link">
        <i class="fas fa-home"></i> Home
      </router-link>
      <router-link to="/profile-settings" class="nav-link">
        <i class="fas fa-cog"></i> Settings
      </router-link>
      <router-link to="/my-products" class="nav-link">
        <i class="fas fa-box"></i> My Products
      </router-link>
    </nav>

    <div class="profile-container">
      <!-- Profile Header -->
      <div class="profile-header">
        <div class="profile-picture-container">
          <img 
            :src="profilePictureUrl" 
            alt="Profile Picture" 
            class="profile-image"
          />
          <div class="upload-overlay" @click="$refs.fileInput.click()">
            <i class="fas fa-camera"></i>
          </div>
        </div>
        <input 
          type="file" 
          ref="fileInput" 
          @change="handleFileSelect" 
          accept="image/*"
          style="display: none"
        />
        <div v-if="selectedFile" class="upload-actions">
          <button @click="uploadPicture" class="upload-btn">
            <i class="fas fa-upload"></i> Upload Picture
          </button>
          <button @click="cancelUpload" class="cancel-btn">
            <i class="fas fa-times"></i> Cancel
          </button>
        </div>
      </div>

      <!-- Profile Info -->
      <div class="profile-info">
        <h2>{{ user.name }}</h2>
        <p class="email">{{ user.email }}</p>
        <p class="contact">{{ user.number }}</p>
        <p class="address">{{ user.address }}</p>
      </div>

      <!-- Review Section -->
      <div class="review-section">
        <h3>Share Your Experience</h3>
        <div class="star-rating">
          <div class="stars">
            <i v-for="n in 5" 
               :key="n" 
               class="fas fa-star"
               :class="{ active: n <= rating }"
               @click="rating = n"
            ></i>
          </div>
        </div>
        <textarea 
          v-model="reviewMessage" 
          placeholder="Write your review here..."
          class="review-input"
        ></textarea>
        <button @click="submitReview" class="submit-review-btn">
          Submit Review
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'MyProfile',
  data() {
    return {
      user: {
        name: '',
        email: '',
        number: '',
        address: '',
        profile_picture: null,
        profile_picture_url: null
      },
      selectedFile: null,
      rating: 0,
      reviewMessage: ''
    }
  },
  computed: {
    profilePictureUrl() {
      if (this.user.profile_picture_url) {
        return this.user.profile_picture_url;
      }
      if (this.user.profile_picture) {
        return `/storage/profile_pictures/${this.user.profile_picture}`;
      }
      return '/images/admin.png';
    }
  },
  methods: {
    handleFileSelect(event) {
      const file = event.target.files[0];
      if (file) {
        this.selectedFile = file;
      }
    },
    
    async uploadPicture() {
      if (!this.selectedFile) return;

      const formData = new FormData();
      formData.append('profile_picture', this.selectedFile);

      try {
        const response = await axios.post('/profile/upload-picture', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });
        
        if (response.data.status === 'success') {
          this.user.profile_picture = response.data.path || this.selectedFile.name;
          this.user.profile_picture_url = response.data.url;
          this.selectedFile = null;
          alert('Profile picture updated successfully!');
          
          await this.fetchUserData();
        } else {
          throw new Error(response.data.message || 'Upload failed');
        }
      } catch (error) {
        console.error('Full error:', error);
        console.error('Error response:', error.response?.data);
        alert(`Failed to upload profile picture: ${error.response?.data?.message || error.message}`);
      }
    },

    cancelUpload() {
      this.selectedFile = null;
      this.$refs.fileInput.value = '';
    },

    async fetchUserData() {
      try {
        const response = await axios.get('/profile');
        if (response.data && response.data.user) {
          this.user = {
            name: '',
            email: '',
            number: '',
            address: '',
            profile_picture: null,
            profile_picture_url: null,
            ...response.data.user
          };

          if (response.data.profile_picture_url) {
            this.user.profile_picture_url = response.data.profile_picture_url;
          }

          console.log('Current user data:', this.user);
          console.log('Profile picture URL:', this.profilePictureUrl);
        }
      } catch (error) {
        console.error('Error fetching user data:', error);
      }
    },
    async submitReview() {
      if (!this.rating || !this.reviewMessage) {
        alert('Please provide both rating and review message');
        return;
      }

      try {
        await axios.post('/review', {
          rating: this.rating,
          message: this.reviewMessage
        });
        
        this.rating = 0;
        this.reviewMessage = '';
        alert('Review submitted successfully!');
      } catch (error) {
        console.error('Error submitting review:', error);
      }
    }
  },
  mounted() {
    this.fetchUserData();
  }
}
</script>

<style scoped>
.profile-page {
  padding: 20px;
  max-width: 800px;
  margin: 0 auto;
}

.profile-nav {
  display: flex;
  justify-content: center;
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

.profile-container {
  background: white;
  border-radius: 10px;
  padding: 30px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.profile-header {
  text-align: center;
  margin-bottom: 30px;
}

.profile-picture-container {
  position: relative;
  width: 150px;
  height: 150px;
  margin: 0 auto;
  border-radius: 50%;
  overflow: hidden;
}

.profile-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.upload-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, 0.5);
  padding: 8px;
  color: white;
  text-align: center;
  cursor: pointer;
  transition: background-color 0.3s;
}

.upload-overlay:hover {
  background: rgba(0, 0, 0, 0.7);
}

.profile-info {
  text-align: center;
  margin-bottom: 30px;
}

.profile-info h2 {
  margin-bottom: 10px;
  color: #333;
}

.profile-info p {
  color: #666;
  margin: 5px 0;
}

.review-section {
  border-top: 1px solid #eee;
  padding-top: 30px;
}

.stars {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin: 15px 0;
}

.fa-star {
  color: #ddd;
  cursor: pointer;
  font-size: 24px;
}

.fa-star.active {
  color: #ffd700;
}

.review-input {
  width: 100%;
  height: 100px;
  margin: 10px 0;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  resize: vertical;
}

.submit-review-btn {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  width: 100%;
  font-size: 16px;
  transition: background-color 0.3s;
}

.submit-review-btn:hover {
  background-color: #45a049;
}

.upload-actions {
  margin-top: 15px;
  display: flex;
  gap: 10px;
  justify-content: center;
}

.upload-btn, .cancel-btn {
  padding: 8px 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
  transition: background-color 0.3s;
}

.upload-btn {
  background-color: #4CAF50;
  color: white;
}

.upload-btn:hover {
  background-color: #45a049;
}

.cancel-btn {
  background-color: #666;
  color: white;
}

.cancel-btn:hover {
  background-color: #555;
}
</style> 