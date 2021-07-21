/**
 * shared.module.ts
 * 
 * Module for shared Angular elements that are used by every module.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-05 / Anne Naumann
 */
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';

@NgModule({
  declarations: [],
  imports: [],
  exports: [
    CommonModule,
    MatButtonModule,
    MatIconModule
  ]
})
export class SharedModule { }
