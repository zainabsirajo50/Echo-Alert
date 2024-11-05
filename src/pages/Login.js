import React, { useState } from 'react';

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
      <div>
        <h2>Sign In</h2>

        <form onSubmit={handleSubmit}>
        <label>Email</label>
        <input
            name='email'
            placeholder="Email"
            type='email'
            value={email}
            required
        />

        <label>Password</label>
        <input
            name='password'
            placeholder="Password"
            type='password'
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
        />
        <button type="submit">Login</button>
        </form>
      </div>
    );
  }
  
  export default Login;