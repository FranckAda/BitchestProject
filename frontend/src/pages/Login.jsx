import React, { useState } from 'react';

export default function Login() {
    const [register, setRegister] = useState(null);

    function Switch () {
        setRegister(!register);
    }

    return (
        <div>
            <input type="text" placeholder='email adress'/>
            <input type="password" name="password" id="" placeholder='password' />
            {register ? 
                <input type="password" placeholder="Confirm password" /> 
                : <></>
            }

            <button>Login</button>
            <button onClick={Switch}>
                {!register ? "Switch to register" : "Switch to login"}
            </button>
        </div>
    );
};