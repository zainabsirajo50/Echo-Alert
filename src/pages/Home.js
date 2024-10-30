import React from 'react';
import { Link } from 'react-router-dom';

function Home() {
    return (
        <div className='header'>
          <h1>Eco-Alert</h1>
          <Link to="/login">
            <button>Login</button>
          </Link>

          <Link to="/sign-up">
            <button>Sign Up</button>
          </Link>

        </div>
      );
    }
    
export default Home;