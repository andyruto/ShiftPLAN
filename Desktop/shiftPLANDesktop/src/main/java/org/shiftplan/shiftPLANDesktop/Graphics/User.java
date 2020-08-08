package org.shiftplan.shiftPLANDesktop.Graphics;

public class User {
    public String name;
    public int id;

    public User(String name, int id) {
        this.name = name;
        this.id = id;
    }

    @Override
    public String toString() {
        return this.name;
    }

}
