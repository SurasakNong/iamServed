var mandrill = require('node-mandrill')('<Your Api Key Here>');
//send an e-mail to jim rubenstein
mandrill('/messages/send', {
    message: {
        to: [{email: 'git@jimsc.com', name: 'Jim Rubenstein'}],
        from_email: 'you@domain.com',
        subject: "Hey, what's up?",
        text: "Hello, I sent this message using mandrill."
    }
}, function(error, response)
{
    //uh oh, there was an error
    if (error) console.log( JSON.stringify(error) );

    //everything's good, lets see what mandrill said
    else console.log(response);
});