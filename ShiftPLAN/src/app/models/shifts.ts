/**
 * shifts.ts
 *  
 * author: Sascha W.
 * last edit / by: 2021-05-24 / Sascha W.
 */

export interface Shifts {
  id: number, 
  weekday: string; 
  date: string, 
  start: string, 
  end: string, 
  task: string, 
  employees: string[],
  shiftStartDate: string,
  shiftEndDate: string,
  connectedTaskId: number
  }