import React, { Component, Fragment } from 'react'; // Pamiętaj o imporcie Fragmentu!
import LoginForm from './LoginForm';

export default class Login extends Component {
  render() {
    return (
      <>
        <div className='login-bg'>
         <LoginForm />
        </div>
      </>
    );
  }
}