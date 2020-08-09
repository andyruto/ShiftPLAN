/*
 * User.java
 *
 * A temporary User class to test out the graphic overlay.
 *
 * author: Sascha W.
 * last edit / by: 2020-08-08 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop.Graphics;

/**
 * Temporary User class to test out the graphic overlay.
 */
public class User {

    //Initialize variables
    public String name;
    public int id;

    //Construct User
    public User(String name, int id) {
        this.name = name;
        this.id = id;
    }

    //Add tiString-Method
    @Override
    public String toString() {
        return this.name;
    }

}
