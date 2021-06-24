/**
 * shifts-shift.component.spec
 * 
 * Typescript class containing the tests for the 
 * shifts-shift component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-24 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ShiftsShiftComponent } from './shifts-shift.component';

describe('ShiftsShiftComponent', () => {
  let component: ShiftsShiftComponent;
  let fixture: ComponentFixture<ShiftsShiftComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ShiftsShiftComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ShiftsShiftComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
