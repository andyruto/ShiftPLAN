/**
 * splash.component.ts
 * 
 * Main typescript class for the splash component.
 * 
 * author: Sascha W.
 * last edit / by: 2021-06-17 / Sascha W.
 */

import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-splash',
  templateUrl: './splash.component.html',
  styleUrls: ['./splash.component.scss']
})
export class SplashComponent implements OnInit {

  constructor(private router : Router) { }

  ngOnInit(): void {
    setTimeout(()=>{this.router.navigate(['start/wizardAdress/']);}, 1000);

  }

}
