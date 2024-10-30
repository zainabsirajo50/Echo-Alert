import React from 'react';
import { Link } from 'react-router-dom';

function Home() {
    return (
        <div className='header'>
          <h1>Eco-Alert</h1>
          <Link to="/Login-Page">
            <button>Login</button>
          </Link>

          <Link to="/SignUp-Page">
            <button>Sign Up</button>
          </Link>

        </div>
      );
    }
    
export default Home;