import React, { useState } from 'react';

function Login() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');

    return (
      <div>
        <h2>Sign In</h2>
        <form>
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
            required
        />
        <button type="submit">Login</button>
        </form>
      </div>
    );
  }
  
  export default Login;