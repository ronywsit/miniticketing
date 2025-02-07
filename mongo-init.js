
db.createUser(
        {
            user: "rocket9",
            pwd: "rocket9",
            roles: [
                {
                    role: "readWrite",
                    db: "ticketing"
                }
            ]
        }
);