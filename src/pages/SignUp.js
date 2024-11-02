import React, { useState } from 'react';
import '../styles/Login-SignUp.css';

function SignUp() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');
    const [userRole, setUserRole] = useState('');

    //Roles for drop down menu
    const roles = [
        { value: '', label: 'Select a role' },
        { value: 'community', label: 'Community Member' },
        { value: 'organization', label: 'Organization Member' },
      ];

      
    const handleSubmit = (e) => {
        e.preventDefault(); //Prevents page refreshing

        // Clear the input fields after submission
        setEmail('');
        setPassword('');
        setConfirmPassword('');
        setUserRole('');
    };


    return (
      <>
      <header></header> 
      <div className="signup-form"> 
        <h2>Sign Up</h2>

        <form onSubmit={handleSubmit}>
          <div className='form-group'>
          <label>Email</label>
          <input
              name='email'
              placeholder="Email"
              type='email'
              value={email}
              required
          />
        </div>

        <div className='form-group'>
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

        <div className='form-group'>
          <label>Confirm Password</label>
          <input
              name='cpassword'
              placeholder="Password"
              type='password'
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
              required
          />
        </div>

        <label>Which role are you?</label>
        <select value={userRole} onChange={(e) => setUserRole(e.target.value)} required>
        {roles.map((role) => (
            <option key={role.value} value={role.value}>
            {role.label}
            </option>
        ))}
        </select>

        <button type="submit" className="submit-button">Sign Up</button>
        </form>
        <p className="signup-link">Already have an account? <a href="/login">Sign In</a></p>
      </div>
      </>
    );
  }
  
  export default SignUp;