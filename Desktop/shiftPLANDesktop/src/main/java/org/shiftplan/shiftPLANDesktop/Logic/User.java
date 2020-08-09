/*
 * User.java
 *
 * Factory for Users.
 *
 * author: Sascha W.
 * last edit / by: 2020-08-09 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop.Logic;

/**
 * User Class for Information transport to API.
 */
public class User {

    //Initialize variables
    private String action;
    private String typeOfAction;
    private String filterType;
    private String filter;
    final private String TYPEOFOBJECT = "User";
    private int id;
    private String name;
    private int hidden;
    private int weekly_working_minutes;
    private int working_week_days;
    private int year_vacation_days;
    private String password_hash;

    //Construct User
    public User() {
    }

    //Getter
    public String getAction() {
        return action;
    }

    public String getTypeOfAction() {
        return typeOfAction;
    }

    public String getFilterType() {
        return filterType;
    }

    public String getName() {
        return name;
    }

    public int getHidden() {
        return hidden;
    }

    public int getWeekly_working_minutes() {
        return weekly_working_minutes;
    }

    public int getWorking_week_days() {
        return working_week_days;
    }

    public int getYear_vacation_days() {
        return year_vacation_days;
    }

    public String getPassword_hash() {
        return password_hash;
    }

    //Setter
    public void setAction(String action) {
        this.action = action;
    }

    public void setTypeOfAction(String typeOfAction) {
        this.typeOfAction = typeOfAction;
    }

    public void setFilterType(String filterType) {
        this.filterType = filterType;
    }

    public void setFilter(String filter) {
        this.filter = filter;
    }

    public void setName(String name) {
        this.name = name;
    }

    public void setHidden(int hidden) {
        this.hidden = hidden;
    }

    public void setWeekly_working_minutes(int weekly_working_minutes) {
        this.weekly_working_minutes = weekly_working_minutes;
    }

    public void setWorking_week_days(int working_week_days) {
        this.working_week_days = working_week_days;
    }

    public void setYear_vacation_days(int year_vacation_days) {
        this.year_vacation_days = year_vacation_days;
    }

    public void setPassword_hash(String password_hash) {
        this.password_hash = password_hash;
    }

    //Add toString-Method
    @Override
    public String toString() {
        return this.name;
    }

}
