/*
 * User.java
 *
 * Class for Users.
 *
 * author: Moritz W.
 * last edit / by: 2020-06-04 / Moritz W.
 */
package org.shiftplan.shiftPLANDesktop.Logics.Core_Classes;

public class User {
    private String username;
    private int userID;
    private boolean hidden;
    private int weeklyWorkingMinutes;
    private int workingWeekDays;
    private int yearVacationDays;

    public User(String username,int userID,boolean hidden,int weeklyWorkingMinutes,int workingWeekDays,int yearVacationDays){
        this.username=username;
        this.userID=userID;
        this.hidden=hidden;
        this.weeklyWorkingMinutes=weeklyWorkingMinutes;
        this.workingWeekDays=workingWeekDays;
        this.yearVacationDays=yearVacationDays;

    }
}
