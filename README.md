# Phone verification

Finally trying to make a working phone verification service for Moodle! Check it out, and feel free to make PRs! Let me know if there are any security issues. Other issues may take me a long time to get to, lol. Enjoy!

###Tests for tool_phoneverification_verify_otp($otp)
We need to account for all possible scenarios:

User could have an OTP for phone 1 OR phone 2 in $SESSION
User could have an OTP for phone 1 AND phone 2 in $SESSION
User could have no OTPs in the $SESSION, thus a DB query is necessary.

User could have an OTP for phone 1 OR phone 2 in $DB
User could have an OTP for phone 1 AND phone 2 in $DB
User could have no OTPs in the $DB, thus they need to submit a phone number for verification.

What if the user has OTPs for both phones out, but is trying to verify phone2 first?
– Let's only allow one phone verification at a time.
