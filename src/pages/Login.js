import React, { useState } from 'react';
import '../styles/Login-SignUp.css';

function Login() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault(); //Prevents page refreshing

        // Clear the input fields after submission
        setEmail('');
        setPassword('');
    };

    return (
      <>
        <header></header> 
        <div className="login-form">
          <h2>Sign In</h2>

          <form onSubmit={handleSubmit}>
            <div className="form-group">
            <label>Email</label>
            <input
                name='email'
                placeholder="Email"
                type='email'
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
            />
          </div>

          <div className="form-group">
            <label>Password</label>
            <input
                name='password'
                placeholder="Password"
                type='password'
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
            />
          </div>
          <button type="submit" className="submit-button">Sign In</button>
          </form>
          <p className="signup-link">Don't have an account? <a href="/sign-up">Sign up here</a></p> 
        </div>
      </>
    );
  }
  
  export default Login;