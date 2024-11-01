import React, { useState } from 'react';
import './LoginForm.css'; 

const LoginForm = ({ onLogin }) => {
  // State to hold email and password inputs
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  // Handle form submission
  const handleSubmit = (e) => {
    e.preventDefault(); // Prevent the default form submission behavior
    onLogin({ email, password }); // Call the onLogin function passed as a prop
  };

  return (
    <>
      <header></header> 
      <div className="login-form">
        <h2>Login</h2>
        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label>Email:</label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)} // Update email state on change
              required 
            />
          </div>
          <div className="form-group">
            <label>Password:</label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)} // Update password state on change
              required
            />
          </div>
          <button type="submit" className="submit-button">Login</button> 
        </form>
        <p className="signup-link">Don't have an account? <a href="/signup">Sign up here</a></p> 
      </div>
    </>
  );
};

export default LoginForm; 