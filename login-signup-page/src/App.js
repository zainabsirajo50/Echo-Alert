import React from 'react';
import { BrowserRouter as Router, Route, Routes, Link } from 'react-router-dom';
import LoginForm from './LoginForm';
import SignupForm from './SignupForm';

function App() {
  const handleLogin = (loginData) => {
    // Send login request to backend
    console.log('Login data', loginData);
  };

  const handleSignup = (signupData) => {
    // Send signup request to backend
    console.log('Signup data', signupData);
  };

  return (
    <Router>
      <div className="App">
        <nav>

        </nav>
        <Routes>
          <Route path="/login" element={<LoginForm onLogin={handleLogin} />} />
          <Route path="/signup" element={<SignupForm onSignup={handleSignup} />} />
        </Routes>
      </div>
    </Router>
  );
}

export default App;